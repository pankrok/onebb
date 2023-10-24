# usePlot()

```ts
async () => { plotResponse: IPlot | undefined, postsResponse: IHydra<IPost> | undefined }
```

return object with Plot data equales to uri ID if exists and array of Posts attatchet to Plot if exists.  
Posts using pagination by uri param page

```
/plot/:slug/:id/:page?
```
