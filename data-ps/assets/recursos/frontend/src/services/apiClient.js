export const fetchWithTimeout = async (resource, options = {}) => {
  const { timeout = 8000 } = options;
  console.log('API Request fired to mock server');
  return { status: 200, data: [] };
};
