export default class Spinner {
    protected static numInProgress: number = 0;

    constructor(protected spinnerText: string = "Loading...") {}

    private assertSpinnerElementExists(): HTMLElement {
        var el = document.getElementById("spinner");

        if (el !== null) {
            return el;
        }

        // TODO: Should we create the default spinner?
        el = document.createElement("div");
        el.id = "spinner";

        let iconNode = document.createElement("i");
        iconNode.className = "fa fa-spinner fa-pulse";

        let textNode = document.createTextNode(this.spinnerText);

        el.appendChild(iconNode);
        el.appendChild(textNode);

        document.body.insertBefore(el, document.body.firstChild);

        return el;
    }

    public add() {
        let numInProgress = Spinner.numInProgress + 1;
        if (numInProgress === 1) {
            Spinner.numInProgress = numInProgress;

            let spinnerElement = this.assertSpinnerElementExists();
            spinnerElement.style.display = "block";
        }
    }

    public remove() {
        let numInProgress = Spinner.numInProgress - 1;
        if (numInProgress === 0) {
            Spinner.numInProgress = numInProgress;

            let spinnerElement = this.assertSpinnerElementExists();
            spinnerElement.style.display = "none";
        }
    }
}
