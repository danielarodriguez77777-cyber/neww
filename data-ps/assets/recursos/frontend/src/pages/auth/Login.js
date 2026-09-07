export const LoginView = () => {
  const handleLogin = () => console.log('Simulating login submission...');
  return <form onSubmit={handleLogin} />;
};
