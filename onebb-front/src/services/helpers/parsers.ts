import moment from "moment";

export const parseUsername = (username: string, groupe: string): string => {
  return groupe.replace("{{username}}", username);
};

export const parseDate = (value: Date|string): string => {
  return moment(String(value)).format("YYYY-MM-DD");
};

