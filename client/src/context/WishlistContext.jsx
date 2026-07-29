import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { wishlistAPI } from '../services/api';
import { useCustomerAuth } from './CustomerAuthContext';

const WishlistContext = createContext();

export const WishlistProvider = ({ children }) => {
  const [wishlistIds, setWishlistIds] = useState([]);
  const [loading, setLoading] = useState(false);
  const { isAuthenticated } = useCustomerAuth();

  const loadIds = useCallback(async () => {
    if (!isAuthenticated) { setWishlistIds([]); return; }
    setLoading(true);
    try {
      const res = await wishlistAPI.getIds();
      setWishlistIds(res.data.data || []);
    } catch { setWishlistIds([]); }
    finally { setLoading(false); }
  }, [isAuthenticated]);

  useEffect(() => { loadIds(); }, [loadIds]);

  const toggleWishlist = async (productId) => {
    if (!isAuthenticated) return false;
    try {
      const res = await wishlistAPI.toggle(productId);
      if (res.data.action === 'added') {
        setWishlistIds(prev => [...prev, productId]);
      } else {
        setWishlistIds(prev => prev.filter(id => id !== productId));
      }
      return res.data.action;
    } catch { return false; }
  };

  const isWishlisted = (productId) => wishlistIds.includes(productId);

  return (
    <WishlistContext.Provider value={{ wishlistIds, toggleWishlist, isWishlisted, loading }}>
      {children}
    </WishlistContext.Provider>
  );
};

export const useWishlist = () => {
  const context = useContext(WishlistContext);
  if (!context) throw new Error('useWishlist must be used within WishlistProvider');
  return context;
};
