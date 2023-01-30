import { SKIN } from "@/services/api/obbResources";
import { ISkinResponse } from "@/interfaces/responseInterface";
import api from "@/services/api/api";

export const actions = {
  async get({ commit }: any) {
    api
      .get<ISkinResponse[]>({resource: SKIN, query: "?active=1"})
      .then((res) => {
        if (res?.body) {
          const [data] = res.body;
          commit("setBoxes", data.skinBoxes);
        }        
      });
  },
};
