class List extends Element {
    constructor(node, iteration) {
        super(node, "List", iteration, "list_default", 4, ["list_title"], ["Category"]);
        this.listTitle();
    }

    listTitle() {
        const listTitle = this.newElement.getAttribute("list_title");
        if(listTitle!==null) {
            let title = this.createElement('h2',"",`${listTitle}`);
            this.newElement.removeAttribute("list_title");
            this.newElement.prepend(title);
        }
    }

    render() {
        let categoryHolder = document.createElement('div');
        categoryHolder.setAttribute('outlook', 'list_category_holder_default');
        let categories = this.element.getElementsByTagName('Category');
        const length = categories.length;
        if(length!=this.element.children.length) {
            console.warn(`There is unallowed tag. (${this.iteration} of List Tag)`);
        }
        for(let i=0; i<length; i++) {
            categoryHolder.appendChild(new Category(categories[0], 0).newElement);
        }
        this.newElement.appendChild(categoryHolder);
    }
}