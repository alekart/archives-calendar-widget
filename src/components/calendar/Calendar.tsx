import { useEffect } from 'react';
import { ArcwConfig } from '../../interfaces';
import { Navigation } from './Navigation';


export function Calendar({config} : {config: ArcwConfig}) {
  useEffect(() => {
    async function fetchPosts(categoryId) {
      try {
        const response = await fetch(`https://your-wordpress-site.com/wp-json/wp/v2/posts?categories=${categoryId}`);
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        const posts = await response.json();
        return posts;
      } catch (error) {
        console.error('There has been a problem with your fetch operation:', error);
      }
    }


  }, []);

  return <>
    <Navigation options={[
      {month: 0, year: 2020},
      {month: 1, year: 2023},
      {month: 4, year: 2024},
    ]} active={{month: 4, year: 2024}} mode={config.mode} onChange={() => {
    }}/>
  </>
}
