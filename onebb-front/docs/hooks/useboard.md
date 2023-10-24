# useBoard()

```ts
async () => { board: IBoard | undefined, plots: IPlot[] | undefined }
```

return object with Board data equales to uri ID if exists and array of Plots attatchet to Board if exist.  
Plots using pagination by uri param page

```
/board/:slug/:id/:page?
```
