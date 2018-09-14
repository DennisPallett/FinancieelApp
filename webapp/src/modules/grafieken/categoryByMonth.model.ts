export interface ICategoryByMonth {
  categories: string[],
  series: [
    {
      name: string,
      data: number[],
      stack: string
    }
  ]
}
