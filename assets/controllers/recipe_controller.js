import { Controller } from "@hotwired/stimulus";

import query from "../query.js";

/* stimulusFetch: 'lazy' */
export default class RecipeController extends Controller {
  async share() {
    const request = new Request(this.element.action, { method: "POST" });
    const response = await query(request);
    void navigator.share({ url: response.url });
  }
}
