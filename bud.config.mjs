// @ts-check

/**
 * Build configuration
 *
 * @see {@link https://bud.js.org/guides/configure}
 * @param {import('@roots/bud').Bud} app
 */
export default async (app) => {
  app
    /**
     * Application entrypoints
     */
    .entry({
      app: ["scripts/app", "styles/app"],
    })

    /**
     * Directory contents to be included in the compilation
     */
    .assets(["images", "svg", "fonts"])

    /**
     * Matched files trigger a page reload when modified
     */
    .watch(["src/templates/*"])

    /**
     * Proxy origin (`WP_HOME`)
     */
    .proxy("http://books.local/")

    /**
     * Development origin
     */
    .serve("http://localhost:3004")
    
    /**
     * URI of the `public` directory
     */
    .setPublicPath("/wp-content/plugins/books/dist/")

};
