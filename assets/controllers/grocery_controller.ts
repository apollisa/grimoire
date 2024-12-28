import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class GroceryController extends Controller {
  declare listValue: string[];

  static values = { list: Array };

  export(): void {
    void navigator.share({ text: this.listValue.join("\n") });
  }
}
