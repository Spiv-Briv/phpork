class DeleteButton extends RedirectButton {
    constructor(node, iterator) {
        super(node, "DeleteButton", iterator, "delete_button_default", ["request", "confirmText"], [], ["action", "controller", "text"], [])
        this.subrender();
    }

    subrender() {
        this.form.setAttribute("method", "POST");
        const controller = this.element.getAttribute('controller');
        if (controller !== null) {
            let input = this.createElement('input',`type=hidden,name=controller,value=${controller}`);
            this.form.removeAttribute("controller");
            this.form.appendChild(input);
        }

        const fun = this.element.getAttribute('function');
        if (fun !== null) {
            let input = this.createElement('input',`type=hidden,name=function,value=${fun}`);
            this.form.removeAttribute("function");
            this.form.appendChild(input);
        }
        let input = this.createElement('input',`type=submit,value=${this.form.getAttribute("text")}`);
        this.form.removeAttribute('text');
        const preventDefault = this.element.getAttribute('prevent_default');
        if (preventDefault === null) {
            input.setAttribute("outlook", this.outlookName);
        }
        else {
            input.removeAttribute('prevent_default');
        }
        input.addEventListener("click", (e) => {
            e.preventDefault();
            confirm("Czy chcesz to zrobic?");
        });
        this.form.appendChild(input);
    }
}