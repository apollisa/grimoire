import { Controller } from "@hotwired/stimulus";

/* stimulusFetch: 'lazy' */
export default class GroceryController extends Controller {
  static values = { list: Array };

  export() {
    void navigator.share({ text: this.listValue.join("\n") });
  }
}
