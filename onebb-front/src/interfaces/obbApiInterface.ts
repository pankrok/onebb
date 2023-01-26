export interface IObbApi {
  isLoading: boolean;
  data: any;
}

export interface IBox {
  id: number;
  box: IBoxConfig;
  page: string;
  position: string;
  active: boolean;
}

export interface IBoxes {
  top: IBoxConfig[];
  bottom: IBoxConfig[];
  left: IBoxConfig[];
  right: IBoxConfig[];
}

export interface IBoxConfig {
  engine: string;
  html: string;
  id: number;
  name: string;
}

export interface IBoxStore {
  Home: IBoxes;
  Category: IBoxes;
  Board: IBoxes;
  Plot: IBoxes;
  Page: IBoxes;
  NewPlot: IBoxes;
  SignUp: IBoxes;
  Profile: IBoxes;
  Userlist: IBoxes;
  Validation: IBoxes;
  UserConfig: IBoxes;
  ResetPassword: IBoxes;
  ResetPasswordValidation: IBoxes;
}

export interface ISkinResponse {
  skinBoxes: IBoxes;
}

export interface IBoard {
  id: number;
  name: string;
  description: string;
  parent: Array<string>;
  active: boolean;
  updated_at: string;
  slug: string;
  priority: number;
  plots_no: number;
  posts_no: number;
  last_active_user: {
    username: number;
    banned: boolean;
    user_group: {
      html_code: string;
    };
    avatar: string;
    slug: string;
  };
}

export interface ICategory {
  id: number;
  name: string;
  active: boolean;
  boards: IBoard[];
  slug: string;
  prioity: number;
  meta_desc: string;
}
