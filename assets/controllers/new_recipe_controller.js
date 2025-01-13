import { Controller } from "@hotwired/stimulus";

import query from "../query.js";

/* stimulusFetch: 'lazy' */
export default class NewRecipeController extends Controller {
  static values = { url: String };
  static targets = ["dialog", "form", "submit"];

  submitTargetconnected(target) {
    target.disabled = true;
  }

  async open() {
    const content = await query(this.urlValue);
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
