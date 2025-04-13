export interface Video {
  id: number
  title: string
  thumbnail_url: string | null
  views: number
  channel: {
    name: string
  }
}
