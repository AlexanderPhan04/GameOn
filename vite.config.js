import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { resolve } from "path";
import { existsSync, rmSync, cpSync } from "fs";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        {
            name: "copy-build",
            closeBundle() {
                try {
                    const src = resolve(process.cwd(), "public/build-temp");
                    const dest = resolve(process.cwd(), "public/build");

                    if (existsSync(src)) {
                        // Xóa build cũ nếu có (bỏ qua lỗi nếu bị lock)
                        try {
                            if (existsSync(dest)) {
                                rmSync(dest, { recursive: true, force: true });
                            }
                        } catch (e) {
                            console.warn(
                                "Could not remove old build:",
                                e.message
                            );
                        }

                        // Copy file
                        try {
                            cpSync(src, dest, { recursive: true });
                            console.log("✓ Copied build files to public/build");
                        } catch (e) {
                            console.warn(
                                "Could not copy build files:",
                                e.message
                            );
                            console.warn(
                                "Please close browser and try again, or use files from public/build-temp"
                            );
                        }
                    }
                } catch (e) {
                    console.warn("Build copy plugin error:", e.message);
                }
            },
        },
    ],
    build: {
        outDir: resolve(__dirname, "public/build-temp"),
        emptyOutDir: true,
    },
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
