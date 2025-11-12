import axiosServer from "../services/axiosServer";

export default defineEventHandler(async (event) => {
  const { search } = getQuery(event);
  const { apiCEP } = axiosServer();

  const { data } = await apiCEP.get("/json/" + search);

  const uf = (data && data.uf) ? String(data.uf).toUpperCase() : null;
  if (uf !== 'PE') {
    return {
      data: null,
    };
  }

  return {
    data,
  };
});
