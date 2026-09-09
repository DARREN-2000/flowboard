import { useEffect } from 'react';

export const useMercure = <T>(topic: string, onMessage: (data: T) => void) => {
  useEffect(() => {
    const url = new URL(import.meta.env.VITE_MERCURE_URL || 'http://localhost:3000/.well-known/mercure');
    url.searchParams.append('topic', topic);
    
    const eventSource = new EventSource(url.toString(), { withCredentials: true });
    
    eventSource.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);
        onMessage(data);
      } catch (err) {
        console.error('Failed to parse Mercure message', err);
      }
    };

    return () => {
      eventSource.close();
    };
  }, [topic, onMessage]);
};
