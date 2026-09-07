export const DashboardCard = ({ title, value }) => (
  <div className='p-6 bg-white shadow rounded'>
    <h3>{title}</h3>
    <p className='text-2xl font-bold'>{value}</p>
  </div>
);
