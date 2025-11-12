import axiosServer from "../services/axiosServer";

export default defineEventHandler(async (event) => {
  const { search } = getQuery(event);
  const { apiCoordinates } = axiosServer();

  const { data } = await apiCoordinates.get("", {
    params: {
      q: search,
    },
  });

  
  const raw = data ?? [];
  const results = Array.isArray(raw)
    ? raw.filter((item: any) => {
        const address = item.address ?? {};
        const countryCode = (address.country_code || address.country || '')
          .toString()
          .toLowerCase();
        const state = (address.state || address.region || '')
          .toString()
          .toLowerCase();
        // const city = (address.city || address.street || '')
        //   .toString()
        //   .toLowerCase();
        const display = (item.display_name || '').toString().toLowerCase();

        const isBrazil = countryCode === 'br' || display.includes('brazil') || display.includes('brasil');
        const isPE = state.includes('pernambuco') || display.includes('pernambuco') || display.includes(', pe ');
        // const isIgarassu = city.includes('igarassu') || display.includes('Igarassu') || display.includes(', igarassu ');

        // return isBrazil && isPE && (isIgarassu || true);
        return isBrazil && isPE
      })
    : [];

  return {
    data: results,
  };
});
