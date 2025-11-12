import type { LocationType } from "~/types/location.type";

export default class LocationService {
  static async create(location: LocationType) {
    const { $axios } = useNuxtApp();
    try {
      const { data } = await $axios.post("/api/locations", location, {
        headers: {
          "Content-Type": location.photo
            ? "multipart/form-data"
            : "application/json",
        },
      });
      return data;
    } catch (e) {
      console.error("Erro ao criar localização:", e);
      return null;
    }
  }

  // services/LocationService.ts
  static async attachServices(locationId: number, services: number[]) {
    const { $axios } = useNuxtApp();
    try {
      const { data } = await $axios.post(
        `/api/locations/locations/${locationId}/services`,
        {
          services,
        }
      );
      return data;
    } catch (e) {
      console.error("Erro ao associar serviços ao local:", e);
      return null;
    }
  }

  // export const getLocationDetails = async (id: number) => {
  static async getLocationDetails(locationId: number) {
    const { $axios } = useNuxtApp();
    const { data } = await $axios.get(`/api/locations/${locationId}`);
    return data;
  }

  static async getAll(location?: LocationType) {
      const { $axios } = useNuxtApp();
        try {
          const { data } = await $axios.get(`/api/locations/`, { params: location });
          return data;
          } catch (e) {
        console.error('Erro ao buscar locais:', e);
      return [];
     }
    }

    static async deleteLocation(locationId: number) {
    const { $axios } = useNuxtApp();
    try {
      await $axios.delete(`/api/locations/${locationId}`);
      return true;
    } catch (error) {
      console.error(`Erro ao excluir local ${locationId}:`, error);
      return false;
    }
  }

    static async updateLocation(locationId: number, location?: LocationType) {
    const { $axios } = useNuxtApp();
    try {
      await $axios.put(`/api/locations/${locationId}`, location);
      return true;
    } catch (error) {
      console.error(`Erro ao atualizar local ${locationId}:`, error);
      return false;
    }
  }
}
