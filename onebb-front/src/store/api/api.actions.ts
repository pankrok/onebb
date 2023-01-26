import { CATEGORY, BOARD } from "@/services/api/obbResources";
import { ICategory, IBoard } from "@/interfaces/obbApiInterface";
import api from "@/services/api/api";

export const getCategory = async ({ commit }: any, id?: number | null) => {
  api
    .setResource(CATEGORY)
    .get(id)
    .then((res) => {
      commit("setData", res);
    });
};

export const getBoard = async ({ commit }: any, id?: number | null) => {
  api
    .setResource(CATEGORY)
    .get(id)
    .then((res) => {
      commit("setData", res);
    });
};