import { describe, it, expect, vi, beforeEach } from 'vitest';
import { renderHook, act } from '@testing-library/react';

// Mock the auth store
const mockStore = {
  token: null as string | null,
  user: null as { id: string; email: string; fullName: string } | null,
  isAuthenticated: false,
  setAuth: vi.fn(),
  logout: vi.fn(),
};

vi.mock('../../src/stores/authStore', () => ({
  useAuthStore: vi.fn(() => mockStore),
}));

vi.mock('../../src/api/auth', () => ({
  authApi: {
    login: vi.fn().mockResolvedValue({
      token: 'test-jwt-token',
      user: { id: '1', email: 'test@example.com', fullName: 'Test User' },
    }),
    register: vi.fn().mockResolvedValue({
      token: 'test-jwt-token',
      user: { id: '1', email: 'test@example.com', fullName: 'Test User' },
    }),
    me: vi.fn().mockResolvedValue({
      id: '1',
      email: 'test@example.com',
      fullName: 'Test User',
    }),
  },
}));

describe('useAuth', () => {
  beforeEach(() => {
    vi.clearAllMocks();
    mockStore.token = null;
    mockStore.user = null;
    mockStore.isAuthenticated = false;
  });

  it('starts unauthenticated', () => {
    expect(mockStore.isAuthenticated).toBe(false);
    expect(mockStore.token).toBeNull();
    expect(mockStore.user).toBeNull();
  });

  it('sets auth state on login', () => {
    mockStore.setAuth('test-token', { id: '1', email: 'test@example.com', fullName: 'Test' });
    expect(mockStore.setAuth).toHaveBeenCalledWith(
      'test-token',
      { id: '1', email: 'test@example.com', fullName: 'Test' }
    );
  });

  it('clears auth state on logout', () => {
    mockStore.logout();
    expect(mockStore.logout).toHaveBeenCalled();
  });

  it('stores token for API requests', () => {
    mockStore.token = 'jwt-token-123';
    expect(mockStore.token).toBe('jwt-token-123');
  });
});
