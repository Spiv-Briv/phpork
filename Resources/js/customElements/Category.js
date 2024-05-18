class Category extends Element {
    constructor(node, iteration) {
        super(node,"Category", iteration, "list_category_default", 4, [], ["Item"]);
        this.categoryTitle();
    }

    categoryTitle() {
        const categoryTitle = this.newElement.getAttribute("category_title");
        if(categoryTitle!==null) {
            let title = this.createElement('h2',"",`${categoryTitle}`);
            this.newElement.removeAttribute("category_title");
            this.newElement.prepend(title);
        }
    }

    render() {
        let items = this.element.getElementsByTagName('Item');
        const length = items.length;
        if(length!=this.element.children.length) {
            console.warn(`There is unallowed tag. (${this.iteration} of Category Tag)`);
        }
        for(let i=0; i<length; i++) {
            this.newElement.appendChild(new Item(items[0],0).newElement);
        }
    }
}