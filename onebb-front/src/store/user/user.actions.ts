import { LOGIN, LOGOUT, REFRESH_TOKEN } from "@/services/api/obbResources";
import api from "@/services/api/api";
import { ISignIn } from "@/interfaces/FormInterfaces";
import { resolve } from "path";

export const actions = {
  async login({ commit }: any, data: ISignIn) {
    return new Promise((resolve) => {
      // FIXME
      api.post<ISignIn, any>({ resource: LOGIN, body: data }).then((res) => {
        console.log("--------LOGIN--------", { res });
        // FIXME
        // @ts-ignore
        if (res.code === 200) {
          commit("setUser", res.body);
          api.setToken(res.body.token);
        } else {
          commit("unsetUser");
        }

        resolve(res.code);
      });
    });
  },

  async refresh({ commit }: any) {
    api.post({ resource: REFRESH_TOKEN }).then((res) => {
      console.log("--------REFRESH_TOKEN--------", { res });
      if (res.code === 200) {
        commit("setUser", res.body);
        // @ts-ignore
        api.setToken(res.body.token);
      } else {
        commit("unsetUser");
      }
    });
  },

  async logout({ commit }: any) {
    api.post({ resource: LOGOUT }).then((res) => {
      console.log("--------LOGOUT--------", { res });
      commit("unsetUser");
    });
  },
};
