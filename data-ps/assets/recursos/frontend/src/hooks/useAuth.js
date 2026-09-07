import { useState, useEffect } from 'react';

export const useAuth = () => {
  const [user, setUser] = useState(null);
  useEffect(() => {
    setUser({ name: 'Ghost' });
  }, []);
  return user;
};
