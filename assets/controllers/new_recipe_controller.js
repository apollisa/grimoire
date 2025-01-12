import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class NewRecipeController extends Controller {
  static values = { url: String };
  static targets = ["dialog", "form", "submit"];

  async open() {
    const content = await fetch(this.urlValue);
    this.dialogTarget.innerHTML = await content.text();
    this.dialogTarget.showModal();
  }

  update() {
    this.submitTarget.disabled = !this.formTarget.checkValidity();
  }

  close() {
    this.dialogTarget.close();
  }
}
