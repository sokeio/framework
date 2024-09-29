export default{
    state: {
        title: "",
    },
    methods: {
        setTitle(title) {
            this.title = title
        }
    },
    render() {
        return (
            <div>
                <h1>{this.title}</h1>
            </div>
        )
    }
}