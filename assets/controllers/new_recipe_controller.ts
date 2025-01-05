import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class NewRecipeController extends Controller {
  declare readonly dialogTarget: HTMLDialogElement;
  declare readonly formTarget: HTMLFormElement;
  declare readonly submitTarget: HTMLButtonElement;
  declare urlValue: string;

  static values = { url: String };
  static targets = ["dialog", "form", "submit"];

  async open(): Promise<void> {
    const content = await fetch(this.urlValue);
    this.dialogTarget.innerHTML = await content.text();
    this.dialogTarget.showModal();
  }

  update(): void {
    this.submitTarget.disabled = !this.formTarget.checkValidity();
  }

  close(): void {
    this.dialogTarget.close();
  }
}
