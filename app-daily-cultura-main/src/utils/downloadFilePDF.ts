export const downloadFilePDF = (res: any) => {
    const link = document.createElement('a');
    const url = `${import.meta.env.VITE_BASE_URL}/download/pdf/${res.data}`;
    link.href = url;
    link.target =  '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};