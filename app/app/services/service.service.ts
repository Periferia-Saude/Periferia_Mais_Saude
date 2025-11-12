import type {ServiceType} from "~/types/service.type";
import { AxiosError } from "axios";

export default new (class ServiceService {
    async create(service: ServiceType) {
        const { $axios } = useNuxtApp();
            try {
                const { data } = await $axios.post(`/api/services`, service) //api/services/location/{location} 
                return data
            } catch (e) {
                console.error('Erro ao criar Serviço:', e)
                return null
            }
        }

      async getAll(service?: ServiceType) {
        const { $axios } = useNuxtApp();
        try {
        const { data } = await $axios.get(`/api/services`, { params: service });
        return data;
        } catch (e) {
        console.error('Erro ao buscar serviços:', e);
        return [];
        }
  }

    async deleteService(id: number) {
    const { $axios } = useNuxtApp();
    try {
      await $axios.delete(`/api/services/${id}`);
      return true;
    } catch (error) {
      console.error(`Erro ao excluir serviço ${id}:`, error);
      return false;
    }
  }

   async getServiceById(id: number) {
    const { $axios } = useNuxtApp();
    try {
      const { data } = await $axios.get(`/api/service/${id}`);
      return data;
    } catch (error) {
      if (error instanceof AxiosError) {
        console.error(
          `Erro ao buscar serviço ${id}:`,
          error.response?.data || error.message
        );
      } else {
        console.error(`Erro ao buscar serviço ${id}:`, error);
      }
      return null;
    }
  }

})