export const ROUTES = {
  HOME: '/',
  LOGIN: '/login',
  REGISTER: '/register',
  WORKSPACE: (slug: string) => `/w/${slug}`,
  PROJECT_BOARD: (workspaceSlug: string, projectSlug: string) => `/w/${workspaceSlug}/p/${projectSlug}`,
};
