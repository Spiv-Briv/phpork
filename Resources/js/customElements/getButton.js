class GetButton extends RedirectButton {
    constructor(node, iterator) {
        super(node,"GetButton",iterator,"input_submit_default",["request"],[],["action","text"],[]);
        this.subRender();
    }

    subRender() {
        let input = this.createElement('input', `type=submit,value=${this.element.getAttribute('text')}`);
        this.form.removeAttribute('text');
        if(this.element.getAttribute('prevent_default')===null) {
            input.setAttribute("outlook",this.outlookName);
        }
        else {
            input.removeAttribute('prevent_default');
        }
        if(this.element.getAttribute('disabled') !== null) {
            input.setAttribute('disabled','true');
            this.form.removeAttribute('disabled');
        }
        this.form.appendChild(input);
        if(this.element.getAttribute('id')!==null) {
            this.form.id = this.element.getAttribute('id');
        }
        this.form.action = this.element.getAttribute('action');
    }
}