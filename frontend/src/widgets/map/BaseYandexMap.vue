<template>
  <div ref="mapElement" class="h-[520px] w-full overflow-hidden rounded-2xl border border-slate-200" />
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { loadYandexMaps } from '@/shared/lib/yandex-maps';

interface MapPoint {
  id: number;
  latitude: number;
  longitude: number;
}

const props = defineProps<{
  points: MapPoint[];
  selectedId?: number | null;
  apiKey?: string;
}>();

const emit = defineEmits<{
  select: [id: number];
}>();

const mapElement = ref<HTMLElement | null>(null);
let mapInstance: {
  geoObjects: { add: (object: unknown) => void; removeAll: () => void };
  setBounds?: (bounds: number[][], options?: Record<string, unknown>) => void;
} | null = null;

onMounted(async () => {
  await initMap();
});

watch(
  () => props.points,
  async () => {
    await renderPoints();
  },
  { deep: true },
);

async function initMap(): Promise<void> {
  if (!mapElement.value) {
    return;
  }

  const ymaps = await loadYandexMaps(props.apiKey);

  mapInstance = new ymaps.Map(
    mapElement.value,
    {
      center: props.points.length > 0
        ? [props.points[0].latitude, props.points[0].longitude]
        : [55.751244, 37.618423],
      zoom: props.points.length > 0 ? 10 : 5,
    },
    {
      suppressMapOpenBlock: true,
    },
  );

  await renderPoints();
}

async function renderPoints(): Promise<void> {
  if (!mapInstance) {
    return;
  }

  const ymaps = await loadYandexMaps(props.apiKey);

  mapInstance.geoObjects.removeAll();

  const bounds: number[][] = [];

  for (const point of props.points) {
    const placemark = new ymaps.Placemark(
      [point.latitude, point.longitude],
      {},
      {
        preset: point.id === props.selectedId ? 'islands#redIcon' : 'islands#blueDotIcon',
      },
    );

    placemark.events.add('click', () => {
      emit('select', point.id);
    });

    mapInstance.geoObjects.add(placemark);
    bounds.push([point.latitude, point.longitude]);
  }

  if (bounds.length > 1 && mapInstance.setBounds) {
    mapInstance.setBounds(bounds, {
      checkZoomRange: true,
      zoomMargin: 40,
    });
  }
}
</script>
