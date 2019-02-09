export const GET_CATEGORY = 'category:getCategory';

export function getCategory(category) {
    return {
        type: GET_CATEGORY,
        payload: {
            category: category
        }
    }
}