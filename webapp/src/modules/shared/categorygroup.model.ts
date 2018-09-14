import { ICategory } from "./category.model";

export interface ICategoryGroup {
  key: string,
  name: string,
  categories: ICategory[]
}
