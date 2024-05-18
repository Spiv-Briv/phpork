class Point extends Element {
    constructor(node, iterator) {
        super(node, "Point", iterator, "point_default", 0, [],[],['X_name']);
        if(this.returnCode>=200) {
            return [null,null, null];
        }
        return [this.element.getAttribute('X_name'),this.element.innerText, this.element.getAttribute('color'), this.element.getAttribute('size')];
    }
}