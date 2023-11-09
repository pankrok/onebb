export interface ISkinConfiguration {
  [index: string]: IPosition
  Home: IPosition
  Category: IPosition
  Board: IPosition
  CreatePlot: IPosition
  Plot: IPosition
  Profile: IPosition
  Page: IPosition
  UserConfiguration: IPosition
}

export interface IPosition {
  [index: string]: ISkinBox[]|null
  top: ISkinBox[] | null
  bottom: ISkinBox[] | null
  left: ISkinBox[] | null
  right: ISkinBox[] | null
}

export interface ISkinBox {
  id: number
  name: string
  engine: string
  html?: string
}

export interface ISkinHydra {
  'hydra:member': HydraMember[]
  'hydra:totalItems': number
  'hydra:view': HydraView
  'hydra:search': HydraSearch
}

export interface HydraMember {
  '@context': string
  '@id': string
  '@type': string
  skinBoxes: SkinBox[]
}

export interface SkinBox {
  '@context': string
  '@id': string
  '@type': string
  id: number
  box: Box
  page: string
  position: string
  active: boolean
}

export interface Box {
  '@context': string
  '@id': string
  '@type': string
  id: number
  name: string
  engine: string
  html: string
}

export interface HydraView {
  '@id': string
  type: string
  'hydra:first': string
  'hydra:last': string
  'hydra:previous': string
  'hydra:next': string
}

export interface HydraSearch {
  '@type': string
  'hydra:template': string
  'hydra:variableRepresentation': string
  'hydra:mapping': HydraMapping[]
}

export interface HydraMapping {
  '@type': string
  variable: string
  property: string
  required: boolean
}
