import { SKIN } from "@/services/api/obbResources";
import { ISkinResponse } from "@/interfaces/responseInterface";
import api from "@/services/api/api";

export const actions = {
  async get({ commit }: any) {
    api
      .setResource(SKIN)
      .get("?active=1")
      .then((res) => {
        let data: ISkinResponse = res.body[0];
        commit("setBoxes", data.skinBoxes);
      });
  },
};
