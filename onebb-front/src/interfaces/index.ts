export interface IObbApi {
  isLoading: boolean
  data: any
}

export interface IBox {
  id: number
  box: IBoxConfig
  page: string
  position: string
  active: boolean
}

export interface IBoxes {
  top: IBoxConfig[]
  bottom: IBoxConfig[]
  left: IBoxConfig[]
  right: IBoxConfig[]
}

export interface IBoxConfig {
  engine: string
  html: string
  id: number
  name: string
}

export interface IBoxStore {
  Home: IBoxes
  Category: IBoxes
  Board: IBoxes
  Plot: IBoxes
  Page: IBoxes
  NewPlot: IBoxes
  SignUp: IBoxes
  Profile: IBoxes
  Userlist: IBoxes
  Validation: IBoxes
  UserConfig: IBoxes
  ResetPassword: IBoxes
  ResetPasswordValidation: IBoxes
}

export interface ISkinResponse {
  skinBoxes: IBoxes
}

export interface ILoginCreditionals {
  username: string
  password: string
}

export interface ILogoutResponse {
  logged_out: boolean
}

export interface ITokenResponse {
  token: string|null
  uid: number|null
  acp_enabled: boolean
  mcp_enabled: boolean
  avatar: string|null
  slug: string|null
  code?: number
  message?: string
}

export interface IUser {
  id: number
  username: string
  banned: boolean
  user_group: {
    html_code: string
    group_name: string
  }
  avatar: string
  slug: string
  posts_no: number
  plots_no: number
  warn_lvl: number
}

export interface IRegisterCreditionals {
  username: string
  email: string
  password: string
}

export interface IViolations {
  '@context': string
  violations: Array<{
    code: string
    message: string
    propertyPath: string
  }>
}

export interface IPost {
  id: number
  user: IUser
  content: string
  reputation: number
  hidden: boolean
  edit_by: IUser
  created_at: string
}

export interface IPlot {
  id: number
  name: string
  tags?: unknown
  user: IUser
  active: boolean
  pinned: boolean
  pinned_order: number
  locked: boolean
  hidden: boolean
  views: number
  stars: number
  updated_at: string
  slug: string
  post_no: number
  last_active_user: {
    id: number
    username: string
    banned: boolean
    user_group: {
      html_code: string
      group_name: string
    }
    avatar: string
    slug: string
    posts_no: number
    plots_no: number
    warn_lvl: number
  }
}

export interface IBoard {
  id: number
  name: string
  description: string
  parent: Array<string>
  active: boolean
  updated_at: string
  slug: string
  priority: number
  plots_no: number
  posts_no: number
  last_active_user?: IUser
  plots?: IPlot[]
}

export interface ICategory {
  id: number
  name: string
  active: boolean
  boards: IBoard[]
  slug: string
  prioity: number
  meta_desc: string
}

export interface IHydraView {
  '@id': string
  '@type': string
  'hydra:first'?: string
  'hydra:last'?: string
  'hydra:previous'?: string
  'hydra:next'?: string
}

export interface IHydra<T> {
  '@context': string
  '@id': string
  '@type': string
  'hydra:member': T[]
  'hydra:view'?: IHydraView
}

export interface IAlert {
  name: string
  text: string
  type: 'success' | 'warning' | 'alert' | 'info'
}
