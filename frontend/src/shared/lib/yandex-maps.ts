let yandexMapsPromise: Promise<typeof window.ymaps> | null = null;

declare global {
  interface Window {
    ymaps: {
      ready: (callback: () => void) => void;
      Map: new (
        container: HTMLElement,
        state: { center: number[]; zoom: number },
        options?: Record<string, unknown>,
      ) => YMapInstance;
      Placemark: new (
        coordinates: number[],
        properties?: Record<string, unknown>,
        options?: Record<string, unknown>,
      ) => YMapPlacemark;
    };
  }
}

interface YMapGeoObjects {
  add: (object: unknown) => void;
  removeAll: () => void;
}

interface YMapInstance {
  geoObjects: YMapGeoObjects;
  setBounds?: (bounds: number[][], options?: Record<string, unknown>) => void;
}

interface YMapPlacemark {
  events: {
    add: (eventName: string, handler: () => void) => void;
  };
}

export async function loadYandexMaps(apiKey?: string): Promise<typeof window.ymaps> {
  if (typeof window === 'undefined') {
    throw new Error('Yandex Maps can only be loaded in the browser.');
  }

  if (window.ymaps) {
    return await waitUntilReady();
  }

  if (!yandexMapsPromise) {
    yandexMapsPromise = new Promise((resolve, reject) => {
      const script = document.createElement('script');
      const keyPart = apiKey ? `&apikey=${apiKey}` : '';

      script.src = `https://api-maps.yandex.ru/2.1/?lang=ru_RU${keyPart}`;
      script.async = true;
      script.onload = async () => {
        try {
          const ymaps = await waitUntilReady();
          resolve(ymaps);
        } catch (error) {
          reject(error);
        }
      };
      script.onerror = () => reject(new Error('Не удалось загрузить Yandex Maps.'));
      document.head.appendChild(script);
    });
  }

  return await yandexMapsPromise;
}

function waitUntilReady(): Promise<typeof window.ymaps> {
  return new Promise((resolve) => {
    window.ymaps.ready(() => resolve(window.ymaps));
  });
}
