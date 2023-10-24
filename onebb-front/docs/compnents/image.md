# Image

## Props

| Props   |       Type       | Required |
| ------- | :--------------: | -------: |
| size    | [number, number] |      YES |
| src     |      string      |      YES |
| alt     |      string      |      YES |
| rounded |     boolean      |       NO |

## Usage

```vue
<script setup lang="ts">
const { parsedResponse } = await getUserById(id)
</script>

<template>
  <Image :src="parsedResponse.avatar" :alt="parsedResponse.username" :size="[75, 75]" rounded />
</template>
```
