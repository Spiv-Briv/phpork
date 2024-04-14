document.addEventListener('DOMContentLoaded', () => {
    const nodes = document.getElementsByTagName('body')[0];
    console.log(nodes);
    for (const node of nodes.children) {
        new CustomElement(node);
    }
});

class CustomElement {
    constructor(node) {
        if (node.children.length > 0) {
            for (const nodeElement of node.children) {
                new CustomElement(nodeElement);
            }
        }
        if (node.nodeName === 'GETBUTTON') {
            new GetButton(node);
        }
        if (node.nodeName === 'POSTBUTTON') {
            new PostButton(node);
        }
        if (node.nodeName === 'CONTAINER') {
            new Container(node);
        }
        if(node.nodeName === 'LIST') {
            new List(node);
        }
        if(node.nodeName=='TABPANEL') {
            new TabPanel(node);
        }
    }
}