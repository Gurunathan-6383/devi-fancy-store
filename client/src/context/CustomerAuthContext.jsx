import { createContext, useContext, useState, useEffect } from 'react';
import { customerAPI } from '../services/api';

const CustomerAuthContext = createContext();

export const CustomerAuthProvider = ({ children }) => {
  const [customer, setCustomer] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem('customerToken');
    if (token) {
      customerAPI.verify()
        .then((res) => setCustomer(res.data.customer))
        .catch(() => localStorage.removeItem('customerToken'))
        .finally(() => setLoading(false));
    } else {
      setLoading(false);
    }
  }, []);

  const login = async (email, password) => {
    const res = await customerAPI.login({ email, password });
    localStorage.setItem('customerToken', res.data.token);
    setCustomer(res.data.customer);
    return res.data;
  };

  const signup = async (name, email, phone, password) => {
    const res = await customerAPI.signup({ name, email, phone, password });
    localStorage.setItem('customerToken', res.data.token);
    setCustomer(res.data.customer);
    return res.data;
  };

  const logout = () => {
    localStorage.removeItem('customerToken');
    setCustomer(null);
  };

  return (
    <CustomerAuthContext.Provider value={{ customer, loading, login, signup, logout, isAuthenticated: !!customer }}>
      {children}
    </CustomerAuthContext.Provider>
  );
};

export const useCustomerAuth = () => {
  const context = useContext(CustomerAuthContext);
  if (!context) throw new Error('useCustomerAuth must be used within CustomerAuthProvider');
  return context;
};
