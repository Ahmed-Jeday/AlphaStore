import Head from "next/head";
import dynamic from "next/dynamic";

// Dynamic import to avoid SSR issues with R3F
const ShoeGrid = dynamic(() => import("@/components/grid/ShoeGrid"), {
  ssr: false,
});

export default function HomePage() {
  return (
    <>
      <Head>
        <title>AlphaStore</title>
        <meta
          name="description"
          content="Discover and explore the AlphaStore collection in 3D."
        />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="icon" href="/favicon.ico" />
      </Head>
      <ShoeGrid />
    </>
  );
}
