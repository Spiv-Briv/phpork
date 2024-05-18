class Panel extends Element {
    constructor(node, iterator) {
        super(node, "Panel", iterator, "tab_panel_default",3,[],[],["tabname"]);
        this.newElement.style.display = "none";
        this.newElement.removeAttribute('tabname');
    }
}