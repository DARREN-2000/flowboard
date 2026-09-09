import React from 'react';
import { Link } from 'react-router-dom';

const RegisterPage: React.FC = () => {
  return (
    <div>
      <h2 className="text-2xl font-bold text-center mb-6">Create an account</h2>
      <div className="text-center text-slate-500">
        Registration is currently disabled. <Link to="/login" className="text-primary-600">Go to login.</Link>
      </div>
    </div>
  );
};

export default RegisterPage;
