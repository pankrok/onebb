import { IBox } from "@/interfaces/obbApiInterface";

export const mutations = {
  setBoxes(state: any, boxlist: IBox[]) {
    boxlist.forEach((singleBox: IBox) => {
      console.log((singleBox.page && singleBox.position && singleBox.id));
      if (singleBox.page && singleBox.position && singleBox.id) {
          
          if (state[singleBox.page][singleBox.position] === null) {
              state[singleBox.page][singleBox.position] = {};
          }
          
          state[singleBox.page][singleBox.position].push(singleBox.box)
      }
  })
  },
};
