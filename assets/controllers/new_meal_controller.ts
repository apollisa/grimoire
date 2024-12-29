import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class NewMealController extends Controller {
  declare readonly dialogTarget: HTMLDialogElement;
  declare urlValue: string;

  static values = { url: String };
  static targets = ["dialog"];

  async open(): Promise<void> {
    const content = await fetch(this.urlValue);
    this.dialogTarget.innerHTML = await content.text();
    this.dialogTarget.showModal();
  }

  close(): void {
    this.dialogTarget.close();
  }
}
