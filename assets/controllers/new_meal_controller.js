import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class NewMealController extends Controller {
  static values = { url: String };
  static targets = ["dialog"];

  async open() {
    const content = await fetch(this.urlValue);
    this.dialogTarget.innerHTML = await content.text();
    this.dialogTarget.showModal();
  }

  close() {
    this.dialogTarget.close();
  }
}
