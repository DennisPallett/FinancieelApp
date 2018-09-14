export interface IClassifyResult {
  classifiedCount: number,
  unclassifiedCount: number,
  classified: [
    {
      id: number,
      category: string
    }
  ]

}
