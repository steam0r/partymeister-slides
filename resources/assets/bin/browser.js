const puppeteer = require('puppeteer');

const request = JSON.parse(process.argv[2]);

const callChrome = async () => {
    let browser;
    let page;
    let output;

    try {
        browser = await puppeteer.launch({
            ignoreHTTPSErrors: request.options.ignoreHttpsErrors,
            executablePath: request.options.executablePath,
            args: request.options.args || []
        });

        page = await browser.newPage();

        page._client.send(
            'Emulation.setDefaultBackgroundColorOverride',
            { color: { r: 0, g: 0, b: 0, a: 0 } }
        );

        // EXTRA CODE
        page.on('console', msg => {
            console.log(msg.text());
        });

        // END EXTRACODE

        if (request.options && request.options.dismissDialogs) {
            page.on('dialog', async dialog => {
                await dialog.dismiss();
            });
        }

        if (request.options && request.options.userAgent) {
            await page.setUserAgent(request.options.userAgent);
        }

        if (request.options && request.options.emulateMedia) {
            await page.emulateMedia(request.options.emulateMedia);
        }

        if (request.options && request.options.viewport) {
            await page.setViewport(request.options.viewport);
        }

        const requestOptions = {};

        if (request.options && request.options.networkIdleTimeout) {
            requestOptions.waitUntil = 'networkidle';
            requestOptions.networkIdleTimeout = request.options.networkIdleTimeout;
        } else if (request.options && request.options.waitUntil) {
            requestOptions.waitUntil = request.options.waitUntil;
        }

        await page.goto(request.url, requestOptions);

        if (request.options.delay) {
            await page.waitFor(request.options.delay);
        }

        request.options.omitBackground = true;

        output = await page[request.action](request.options);

        if (!request.options.path) {
            console.log(output.toString('base64'));
        }

        await browser.close();
    } catch (exception) {
        if (browser) {
            await browser.close();
        }

        console.error(exception);

        process.exit(1);
    }
};

callChrome();
