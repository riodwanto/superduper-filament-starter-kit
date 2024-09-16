// resources/js/index.js
var js_default = ({ parentId }) => ({
    parentId,
    sortable: null,
    init() {
        this.sortable = new Sortable(this.$el, {
            group: 'nested',
            draggable: '[data-sortable-item]',
            handle: '[data-sortable-handle]',
            animation: 300,
            ghostClass: 'fi-sortable-ghost',
            dataIdAttr: 'data-sortable-item',
            onSort: () => {
                this.$wire.reorder(
                    this.sortable.toArray(),
                    this.parentId === 0 ? null : this.parentId,
                )
            },
        })
    },
})
export { js_default as default }
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vanMvaW5kZXguanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImV4cG9ydCBkZWZhdWx0ICh7IHBhcmVudElkIH0pID0+ICh7XG4gICAgcGFyZW50SWQsXG4gICAgc29ydGFibGU6IG51bGwsXG5cbiAgICBpbml0KCkge1xuICAgICAgICB0aGlzLnNvcnRhYmxlID0gbmV3IFNvcnRhYmxlKHRoaXMuJGVsLCB7XG4gICAgICAgICAgICBncm91cDogJ25lc3RlZCcsXG4gICAgICAgICAgICBkcmFnZ2FibGU6ICdbZGF0YS1zb3J0YWJsZS1pdGVtXScsXG4gICAgICAgICAgICBoYW5kbGU6ICdbZGF0YS1zb3J0YWJsZS1oYW5kbGVdJyxcbiAgICAgICAgICAgIGFuaW1hdGlvbjogMzAwLFxuICAgICAgICAgICAgZ2hvc3RDbGFzczogJ2ZpLXNvcnRhYmxlLWdob3N0JyxcbiAgICAgICAgICAgIGRhdGFJZEF0dHI6ICdkYXRhLXNvcnRhYmxlLWl0ZW0nLFxuICAgICAgICAgICAgb25Tb3J0OiAoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy4kd2lyZS5yZW9yZGVyKFxuICAgICAgICAgICAgICAgICAgICB0aGlzLnNvcnRhYmxlLnRvQXJyYXkoKSxcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5wYXJlbnRJZCA9PT0gMCA/IG51bGwgOiB0aGlzLnBhcmVudElkXG4gICAgICAgICAgICAgICAgKVxuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH0sXG59KVxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFBLElBQU8sYUFBUSxDQUFDLEVBQUUsU0FBUyxPQUFPO0FBQUEsRUFDOUI7QUFBQSxFQUNBLFVBQVU7QUFBQSxFQUVWLE9BQU87QUFDSCxTQUFLLFdBQVcsSUFBSSxTQUFTLEtBQUssS0FBSztBQUFBLE1BQ25DLE9BQU87QUFBQSxNQUNQLFdBQVc7QUFBQSxNQUNYLFFBQVE7QUFBQSxNQUNSLFdBQVc7QUFBQSxNQUNYLFlBQVk7QUFBQSxNQUNaLFlBQVk7QUFBQSxNQUNaLFFBQVEsTUFBTTtBQUNWLGFBQUssTUFBTTtBQUFBLFVBQ1AsS0FBSyxTQUFTLFFBQVE7QUFBQSxVQUN0QixLQUFLLGFBQWEsSUFBSSxPQUFPLEtBQUs7QUFBQSxRQUN0QztBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQ0o7IiwKICAibmFtZXMiOiBbXQp9Cg==
